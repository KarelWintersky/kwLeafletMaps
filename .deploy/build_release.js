var fileslist = [
    '.htaccess',
    'index.php',
    'map.php',
    'project.php',
    'backend/config.php',
    'backend/required.php',
    'backend/websun',
    'backend/websun/websun.php',
    'storage/gallery.template',
    'templates/maps_list.html',
    'templates/map_view.html',
    'templates/css/common.css',
    'templates/css/leaflet.css',
    'templates/js/html5shiv.js',
    'templates/js/jquery.min.js',
    'templates/js/leaflet',
    'templates/js/leaflet.js',
    'templates/js/leaflet/L.Control.MousePosition.css',
    'templates/js/leaflet/L.Control.MousePosition.js',
    'templates/js/leaflet/singleclick.js'
];

module.exports = function(grunt) {
    var pkgVersion = grunt.file.readJSON('package.json').version;

    grunt.loadNpmTasks('grunt-contrib-compress');

    grunt.initConfig({
        compress: {
            main: {
                options: {
                    archive: function() {
                        return 'zip/lfme_release_' + pkgVersion + '.zip'
                    },
                    pretty: true
                },
                expand: true,
                cwd: '../',
                src: [].concat(fileslist),
                dest: '/'
            }
        }
    });

    grunt.registerTask('default', ['compress']);


};