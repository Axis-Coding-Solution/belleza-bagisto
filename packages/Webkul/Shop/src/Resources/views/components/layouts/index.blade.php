@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="mega-menu.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="mega-menu.js"></script>
    <head>
        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="currency-code" content="{{ core()->getCurrentCurrencyCode() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

        @stack('meta')

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" as="style">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

        <link rel="preload" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" as="style">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap">

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        {!! view_render_event('bagisto.shop.layout.head') !!}
    </head>

    <body>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <div id="app">
            {{-- Flash Message Blade Component --}}
            <x-shop::flash-group />

            {{-- Confirm Modal Blade Component --}}
            <x-shop::modal.confirm />

            
            
            <div class=" bg-[#A81D46] text-[#fff] flex justify-center items-center w-full py-[11px] px-16 border border-t-0 border-b-[1px] border-l-0 border-r-0">
                <p class="md:text-[14px] text-xs ">SAME DAY DELIVERY, ALL AROUND BAHRAIN</p>
            </div>

            <section class=" mx-[10.89%] ">  
                
                {{-- Page Header Blade Component --}}
                @if ($hasHeader)
                    <x-shop::layouts.header />
                @endif
                {!! view_render_event('bagisto.shop.layout.content.before') !!}

                {{-- Page Content Blade Component --}}
                {{ $slot }}

                {!! view_render_event('bagisto.shop.layout.content.after') !!}

                {{-- Page Features Blade Component --}}
                @if ($hasFeature)
                    <x-shop::layouts.features />
                @endif

                
            </section>
            {{-- Page Footer Blade Component --}}
            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
            
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @stack('scripts')

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}

            $(document).ready(function() {
           jQuery(document).ready(function(){
             $(".dropdown").hover(
             function() { $('.dropdown-menu', this).stop().fadeIn("fast");
                },
          function() { $('.dropdown-menu', this).stop().fadeOut("fast");
          });
        });
}
        </script>
    </body>
</html>
