<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AdminLanguage;
use App\Models\GeneralSetting;
use App\Models\LocalizedModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Session\Session as SessionSession;

class Controller extends BaseController
{
    protected $storeSettings;
    protected $storeLocale;
    protected $locales;
    protected $lang;
    protected $adminLocale;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if (!app()->runningInConsole()) {
            $this->storeSettings = resolve('storeSettings');
            $this->storeLocale = resolve('storeLocale');
            $this->adminLocale = resolve('adminLocale');
            $this->locales = resolve('locales');
            $this->lang = resolve('lang');

            $this->middleware('set.locale');
        }

        if (app()->runningInConsole()) {
            $this->storeSettings = new Generalsetting;
        }
    }

    /**
     * Remove Empty Translation fields from an input array and also delete
     * from a LocalizedModel instance
     *
     * The array is in the format:
     * "locale" =>  [
     *      "field" => "value"
     *  ]
     * @param array $input
     * @param LocalizedModel $model
     * @return array
     */
    public function removeEmptyTranslations(array $input, LocalizedModel $model = null, $remove_all = false)
    {
        $removed = [];

        foreach ($this->locales as $locale) {
            if ($locale->locale === $this->lang->locale && !$remove_all) {
                continue;
            }

            if (isset($input[$locale->locale])) {
                $input[$locale->locale] = array_filter($input[$locale->locale]);
                if (empty($input[$locale->locale])) {
                    $removed[] = $locale->locale;
                    unset($input[$locale->locale]);
                }
            }
        }

        if ($model) {
            $model->deleteTranslations($removed);
        }

        return $input;
    }

    /**
     * Keep required fields with translations, if they are provided, or set
     * the translations with the language 1 field. Required fields cannot be
     * null on database.
     *
     * If a LocalizedModel is provided, set the required input with existing
     * data from the model.
     *
     * @param array $input The fields from a form
     * @param array $fields The required field names from the database
     * @param LocalizedModel $model A model to get existing data from
     * @return array
     */
    public function withRequiredFields(array $input, array $fields, LocalizedModel $model = null)
    {
        foreach ($this->locales as $locale) {
            if ($locale->locale === $this->lang->locale) {
                continue;
            }

            foreach ($fields as $field) {
                if ($model) {
                    $input[$locale->locale][$field] = (
                        $model->hasTranslation($locale->locale) ?
                        $model->translate($locale->locale)->{$field} :
                        $model->{$field}
                    );
                } else {
                    $input[$locale->locale][$field] = (
                        isset($input[$locale->locale][$field]) ?
                        $input[$locale->locale][$field] :
                        $input[$this->lang->locale][$field]
                    );
                }
            }
        }

        return $input;
    }

    public function useStoreLocale()
    {
        App::setlocale($this->storeLocale->locale);
    }

    public function useAdminLocale()
    {
        App::setlocale("admin_{$this->adminLocale->name}");
    }

    public function trumbowygUpload(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json('Unauthenticated.');
        }

        $rules = [
            "image" => 'required|mimes:jpeg,jpg,png'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if ($file = $request->file('image')) {
            $name = Str::random(8).time().".".$file->getClientOriginalExtension();
            $file->move('storage/images/trumbowyg/', $name);
        }
        return response()->json([
            'success' => true,
            'file' => asset('storage/images/trumbowyg/'.$name)
        ]);
    }

    protected function generateCaptcha()
    {
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        $pixel = imagecolorallocate($image, 150, 50, 255);
        for ($i=0;$i<500;$i++) {
            imagesetpixel($image, rand()%200, rand()%50, $pixel);
        }

        $font = public_path() . '/assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length-1)];
        $word = '';
        $text_color = imagecolorallocate($image, 100, 100, 100);
        $cap_length = 6;// No. of character in image
        for ($i = 0; $i< $cap_length;$i++) {
            $letter = $allowed_letters[rand(0, $length-1)];
            imagettftext($image, 20, random_int(1, 9), 30+($i*25), 35, $text_color, $font, $letter);
            $word.=$letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for ($i=0;$i<500;$i++) {
            imagesetpixel($image, rand()%200, rand()%50, $pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, public_path() . "/storage/images/capcha_code.png");
    }

    protected function getStoreSettings()
    {
        # Remember Generalsetting Cache for one hour. It will be invalidated if Generalsetting is updated or saved in any way.
        return Cache::remember("storeSettings", 60 * 60, function () {
            $currentUrl = str_replace(
                ['http://', 'https://'],
                '',
                url()->current()
            );
            # Return empty object if there's no migration yet
            if (!Schema::hasTable('generalsettings')) {
                return new Generalsetting;
            }
            $storeSettings = Generalsetting::whereRaw("'{$currentUrl}' LIKE CONCAT(domain,'%')")->first();
            if (!$storeSettings || empty($storeSettings->domain)) {
                $storeSettings = Generalsetting::where('is_default', 1)->first();
            }
            return $storeSettings;
        });
    }

    protected function forgetGeneralSettingsCache()
    {
        if (Cache::has("storeSettings")) {
            Cache::forget("storeSettings");
        }
        $storeSettings = $this->getStoreSettings();
        return $storeSettings;
    }
}
