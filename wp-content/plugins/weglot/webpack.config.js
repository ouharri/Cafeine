const path = require("path");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const webpack = require("webpack");
const env = process.env.NODE_ENV || "development";

module.exports = {
	entry: {
		"front-js": "./app/javascripts/front.js",
		"admin-js": "./app/javascripts/index.js",
		"front-css": "./app/styles/index.scss",
		"admin-css": "./app/styles/admin.scss",
		"front-amp-css": "./app/styles/amp.scss",
		"nav-js" : "./app/javascripts/nav.js"
	},
	output: {
		path: __dirname + "/dist",
		publicPath: "/dist/"
	},
	mode: env,
	module: {
		rules: [
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				include: path.join(__dirname, "node_modules"),
				use: "babel-loader"
			},
			{
				test: /\.(gif|jpe?g|png)$/,
				loader: "url-loader",
				query: {
					limit: 10000,
					name: "images/[name].[ext]"
				}
			},
			{
				test: /\.scss$/,
				use: ExtractTextPlugin.extract({
					fallback: "style-loader",
					use: [
						{
							loader: "css-loader?url=false"
						},
						{
							loader: "sass-loader"
						},
						{
							loader: "postcss-loader"
						}
					]
				})
			}
		]
	},
	plugins: [
		new webpack.DefinePlugin({
			NODE_ENV: env
		}),
		new ExtractTextPlugin({
			filename: "css/[name].css"
		}),
		new CopyWebpackPlugin([
			{ from: "app/images", to: "images" },
			{ from: "app/static", to: "images" },
			{ from: "app/javascripts/selectize.js", to: "selectize.js" }
		])
	]
};
