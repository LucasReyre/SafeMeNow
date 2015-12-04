var path = require("path");

module.exports = function(grunt) {

	grunt.initConfig({
		coffee: {
			'compile-all': {
        options:{
					bare: true
				},
				expand: true,
				flatten: true,
				cwd: '../',
				src: ['coffee/*.coffee'],
				dest: '../coffee/js/',
				ext: '.js'
			},
			'compile-single-file': {
        options:{
					bare: true
				},
				expand: true,
				flatten: true,
				src: ['coffee'],
				dest: '',
				ext: '.js'
			}
		},
		watch: {
			coffee: {
				files: ['../coffee/*.coffee'],
				tasks: ['coffee:compile-single-file'],
				options: {
					spawn: false
				}
			}
		}
	});

	grunt.event.on('watch', function(action, filepath, target) {
			console.log("Path : " + filepath);

			if (filepath.indexOf('.coffee') !== -1) {
  			coffeeConfig = grunt.config( "coffee" );
  			// all coffee file should be generated alone unless we really need to re--build the entire project
  			// so  we need to generate only this file, not all directory, here we update grunt config dynamically
  			coffeeConfig['compile-single-file'].src = [filepath];
				// place it in the js folder, in the Coffee folder
				var compiledPath = filepath.replace("coffee", "coffee"+path.sep+"js" );
        console.log(compiledPath);
				coffeeConfig['compile-single-file'].dest = compiledPath.substring(0, compiledPath.lastIndexOf(path.sep) + 1);
  			console.log(coffeeConfig['compile-single-file'].dest);
  			grunt.config("coffee", coffeeConfig);
		  }
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-coffee');

	grunt.registerTask('default', ['coffee:compile-all', 'watch']);
};
