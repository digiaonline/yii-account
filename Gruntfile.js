module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            styles: {
                files: {
                    "src/assets/css/styles.css": "src/assets/less/styles.less"
                }
            }
        },
        watch: {
            styles: {
                files: ['src/assets/less/**/*.less'],
                tasks: ['less'],
                options: {
                    spawn: false
                }
            }
        }
    });

    grunt.registerTask('default', ['watch']);

};