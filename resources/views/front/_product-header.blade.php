<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li><a href="{{route('front.index')}}">{{ __("Home") }}</a></li>
                    <li>
                        <a href="{{route('front.category',$productt->category->slug)}}">
                            {{$productt->category->name}}
                        </a>
                    </li>
                    @if(!empty($productt->subcategory->name))
                    <li>
                        <a href="{{ route('front.subcat',[
                                  'slug1' => $productt->category->slug,
                                  'slug2' => $productt->subcategory->slug]) }}">
                            {{$productt->subcategory->name}}
                        </a>
                    </li>
                    @if(!empty($productt->childcategory->name))
                    <li>
                        <a href="{{ route('front.childcat',[
                                    'slug1' => $productt->category->slug,
                                    'slug2' => $productt->subcategory->slug,
                                    'slug3' => $productt->childcategory->slug]) }}">
                            {{$productt->childcategory->name}}
                        </a>
                    </li>
                    @endif
                    @endif
                    <li>
                        <a href="{{ route('front.product', $productt->slug) }}">
                            {{ $productt->name }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
