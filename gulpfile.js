var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    mix.sass([
        'main.scss'
    ], 'public/build/css/style.css');

      mix.styles([
       "bower_components/bootstrap/dist/css/bootstrap.css",
       'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
       //'bower_components/datatables/media/css/jquery.dataTables.css',
       //'bower_components/chosen/chosen.min.css',
       //'bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css',
       //'bower_components/jquery-ui/themes/smoothness/jquery-ui.min.css',
      // 'bower_components/multiselect/css/multi-select.css',
       //"css/app.css",
       "bower_components/datetimepicker/jquery.datetimepicker.css",
          'bower_components/toastr/toastr.min.css',
          'bower_components/unibox/css/unibox.min.css',
          'bower_components/rrssb/css/rrssb.css',



      // "assets/css/main.css",
        "build/css/style.css",
          "assets/css/login_modal.css",
          "assets/css/nathan.css",

      ], 'public/css/all.css', 'public/');





     mix.scripts([
      "bower_components/jquery/dist/jquery.js",
      "bower_components/bootstrap/dist/js/bootstrap.js",
      'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
      //'bower_components/datatables/media/js/jquery.dataTables.js',
      //'bower_components/chosen/chosen.jquery.min.js',
      //'bower_components/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js',
      //'bower_components/jquery-ui/jquery-ui.min.js',
      //'bower_components/multiselect/js/jquery.multi-select.js',
         'bower_components/datetimepicker/jquery.datetimepicker.js',
         'bower_components/toastr/toastr.min.js',
        'assets/js/twitter-text.js',
         'bower_components/unibox/js/unibox.min.js',
         'bower_components/linkifyjs/dist/jquery.linkify.js',
         'bower_components/rrssb/js/rrssb.min.js',
         //'bower_components/croppic/croppic.min.js',
        'assets/js/main.js',


     ], 'public/js/all.js', 'public/');



     mix.version(["public/css/all.css", "public/js/all.js"]);

    mix.copy("public/bower_components/bootstrap/dist/fonts/**", "public/build/fonts");

    mix.copy("public/assets/images/**", "public/build/images");
    //mix.copy('public/bower_components/datatables/media/images/**', "public/build/images");
   // mix.copy('public/bower_components/multiselect/img/**', "public/build/img");




});


elixir(function(mix) {
    mix.browserify('./resources/assets/js/react/search.js');
})
