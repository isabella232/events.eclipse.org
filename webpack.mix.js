/*!
 * Copyright (c) 2018 Eclipse Foundation, Inc.
 * 
 * This program and the accompanying materials are made available under the
 * terms of the Eclipse Public License v. 2.0 which is available at
 * http://www.eclipse.org/legal/epl-2.0.
 * 
 * Contributors:
 *   Christopher Guindon <chris.guindon@eclipse-foundation.org>
 * 
 * SPDX-License-Identifier: EPL-2.0
*/

mix = require('laravel-mix');
mix.options({uglify: {uglifyOptions: {compress: false, output: {comments: true}}}});
const { env } = require('minimist')(process.argv.slice(2));

// load site-specific config
if (env && env.site) {
  require(`${__dirname}/webpack.mix.${env.site}.js`);
}
else {
  require(`${__dirname}/webpack.mix.default.js`);
}