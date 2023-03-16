const path = require('path')
module.exports = {
	mode: 'production',
	entry: path.resolve(__dirname, '../../frontend/js/script.js'),
	output: {
		path: path.resolve(__dirname, '../../frontend/js/'),
		filename: 'script.min.js',
	},
};