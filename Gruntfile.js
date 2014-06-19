module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    clean: {
      dist: {
        src: ["dist/"]
      }
    },
    replace: {
      dist: {
        src: ['dist/*.css','dist/**/*.php'],
        overwrite: true,
         replacements: [
        {
          from: '@version@',
          to: '<%= pkg.version %>'
        },
        {
          from: '@plugin_name@',
          to: '<%= pkg.description %>'
        }]
      }
    },
    copy: {
      dist: {
        files: [
          {expand: true, src: ['*.php', 'lib/**'], dest: 'dist/'},
          {expand: true, src: ['assets/css/**', 'assets/js/**', 'assets/fonts/**', 'assets/img/**'], dest: 'dist/'}
        ]
      }
    },
    compress: {
      dist: {
        options: {
          archive: './mmm-plugin.zip',
          mode: 'zip'
        },
        files: [
          { expand: true, cwd: 'dist/', src: ['**/*'] }
        ]
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-text-replace');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-compress');

  grunt.registerTask( 'dist', "Build up distribution folder", ['clean:dist', 'copy:dist','replace:dist','compress:dist']);

};
