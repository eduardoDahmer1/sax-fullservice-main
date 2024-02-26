<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmailTemplateTranslationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('email_template_translations')->delete();

        \DB::table('email_template_translations')->insert(array (
            0 =>
            array (
                'id' => 1,
                'email_template_id' => 1,
                'locale' => 'pt-br',
                'email_subject' => 'Seu pedido foi realizado com sucesso.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá {customer_name},<br>s</span><span lang="pt"><span lang="pt">eu pedido foi bem sucedido!<br></span>
O número do seu pedido é {order_number}.

</span></pre></p>',
            ),
            1 =>
            array (
                'id' => 2,
                'email_template_id' => 2,
                'locale' => 'pt-br',
                'email_subject' => 'E-Commerce - Bem-vindo!',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá {customer_name},
Você se registrou com sucesso no {website_title}. Desejamos que você tenha uma experiência maravilhosa ao usar nosso serviço.

Obrigado!</span></pre></p>',
            ),
            2 =>
            array (
                'id' => 3,
                'email_template_id' => 3,
                'locale' => 'pt-br',
                'email_subject' => 'Sua conta de fornecedor ativada.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá {customer_name},
Sua conta de fornecedor foi ativada com sucesso. Faça login em sua conta e crie sua própria loja.

Obrigado!</span></pre></p>',
            ),
            3 =>
            array (
                'id' => 4,
                'email_template_id' => 4,
                'locale' => 'pt-br',
                'email_subject' => 'Seu plano de assinatura terminará após cinco dias.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"></p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá {customer_name},
A duração do seu plano de assinatura terminará após cinco dias. Renove seu plano, caso contrário, todos os seus produtos serão desativados.

Obrigado!</span></pre><p></p>',
            ),
            4 =>
            array (
                'id' => 5,
                'email_template_id' => 5,
                'locale' => 'pt-br',
                'email_subject' => 'Pedido de verificação.',
                'email_body' => '<pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá {customer_name},
Você pediu para verificar sua conta. Por favor envie uma foto do seu documento.

Obrigado!</span></pre>',
            ),
            5 =>
            array (
                'id' => 6,
                'email_template_id' => 5,
                'locale' => 'es',
                'email_subject' => 'Solicitud de verificación.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"></p><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="es">Hola, {customer_name}:
Solicitó verificar su cuenta. Envíe una foto de su documento.<br>
¡Gracias!</span></pre><p></p>',
            ),
            6 =>
            array (
                'id' => 7,
                'email_template_id' => 4,
                'locale' => 'es',
                'email_subject' => 'Su plan de suscripción finaliza después de cinco días.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="es">Hola, {customer_name}:
Su plan de suscripción vencerá después de cinco días. Renueve su plan, de lo contrario se desactivarán todos sus productos.

¡Gracias!</span></pre></p>',
            ),
            7 =>
            array (
                'id' => 8,
                'email_template_id' => 3,
                'locale' => 'es',
                'email_subject' => 'Su cuenta de proveedor activada.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="es">Hola, {customer_name}:
Su cuenta de proveedor se ha activado correctamente. Inicie sesión en su cuenta y cree su propia tienda.

¡Gracias!</span></pre></p>',
            ),
            8 =>
            array (
                'id' => 9,
                'email_template_id' => 2,
                'locale' => 'es',
                'email_subject' => 'E-Commerce - Bienvenido!',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="es">Hola, {customer_name}:
Te has registrado correctamente en {website_title}. Esperamos que tenga una experiencia maravillosa con nuestro servicio.

¡Gracias!</span></pre></p>',
            ),
            9 =>
            array (
                'id' => 10,
                'email_template_id' => 1,
                'locale' => 'es',
                'email_subject' => 'Su pedido se ha realizado correctamente.',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="es">Hola, {customer_name}:
tu pedido fue exitoso!

Su número de pedido es {order_number}.</span></pre></p>',
            ),
            10 =>
            array (
                'id' => 11,
                'email_template_id' => 6,
                'locale' => 'pt-br',
                'email_subject' => 'Finalize sua Compra!',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Olá, {customer_name}!
Vimos que você não finalizou sua compra. Clique no botão abaixo e continue de onde parou!

Obrigado!</span></pre></p>',
            ),
            11 =>
            array (
                'id' => 12,
                'email_template_id' => 6,
                'locale' => 'es',
                'email_subject' => 'Completa tu compra!',
                'email_body' => '<p class="tw-ta-container hide-focus-ring tw-nfl" id="tw-target-text-container" tabindex="0"><pre class="tw-data-text tw-text-large XcVN5d tw-ta" data-placeholder="Tradução" id="tw-target-text" style="text-align:left" dir="ltr"><span lang="pt">Hola, {customer_name}!
                Hemos visto que no ha completado su compra. ¡Clica en el botón abajo y continúe donde lo dejó!

                ¡Gracias!</span></pre></p>',
            ),
        ));


    }
}
