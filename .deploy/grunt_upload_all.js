var account = require('./account.json');

module.exports = function(grunt) {
    require('time-grunt')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        'ftp-deploy': {
            build: {
                auth: {
                    host: account.host,
                    port: account.port,
                    username: account.username,
                    password: account.password
                },
                src: [
                    '../'],
                dest: account.uploadpath,
                exclusions: [
                    'LICENSE',
                    '.gitignore',
                    '*.md',
                    '.git',
                    '.idea',
                    '.deploy',
                    'account.json',
                    'config.php.example'
		        ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-ftp-deploy');

    grunt.registerTask('default', [ 'ftp-deploy']);
};