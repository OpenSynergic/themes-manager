let mix = require("laravel-mix");
mix
  .setPublicPath("public")
  .disableSuccessNotifications()
  .options({ manifest: false });

mix
  .js("resources/js/main.js", "js")
  .postCss("resources/css/main.css", "css", [require("tailwindcss")]);
