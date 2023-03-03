const mix = require('laravel-mix');
const assetsPath = 'assets';
const distributionPath = 'assets';

mix.setPublicPath(`${distributionPath}`);
mix.sass(`${assetsPath}/scss/styles.scss`, `${distributionPath}/css`).sourceMaps();

mix.minify([
  `${distributionPath}/css/styles.css`,
  `${distributionPath}/js/scripts.js`,
]);
