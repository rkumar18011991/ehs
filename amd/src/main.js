define(['jquery', 'core/ajax', 'core/notification'], function ($, ajax, notification) {
    return {
        init: function () {

            var crs = $('[data-region="ehs-learning"]').data('displaycourse');
            var promises = ajax.call([
                {
                    methodname: 'block_ehs_get_course_modules',
                    args: {
                        courseid: crs
                    }
                }
            ]);

            promises[0].done(function (result) {
                console.log(result);
                var len = result.length;

                $('[data-region="ehs-learning"]').append(
                    '<ul style="list-style-type:none;" id="mod_list"></ul>');
                // $('ul#mod_list').append('<li>'+result.cprogress+'</li>');
                for (var i = 0; i < len; i++) {
                    id = result[i].id;
                    name = result[i].name;
                    url = result[i].url;
                    added = result[i].added;
                    views = result[i].views;
                    activitystatus = result[i].astatus;
                    progress = result[i].cprogress;
                    coursename = result[i].coursename;
                    if (i == 0) {
                        fullcoursename = "<b>Course Name: " + coursename + "</b>";
                        html = "<progress id='file' value=" + progress + " max='100'> " + progress + "% </progress>";
                        courseprogress = "<b> Course progress: </b>";
                        $('ul#mod_list').append('<li>' + fullcoursename + '</li>');
                        $('ul#mod_list').append('<li>' + courseprogress + '</li>');
                        $('ul#mod_list').append('<li>' + html + '</li>');
                    }
                    $('ul#mod_list').append('<li>' + id + ' - <a href="' + url + '">' + name + '</a> - ' + added + ' - ' + activitystatus + '</li>');

                }

                if (len == 0) {
                    $('ul#mod_list').append('<li>No Records Founds</li>');
                }

            }).fail(function () {
                message = 'ERROR';
                notification.addNotification({
                    type: 'error',
                    message: 'Exception: Error Displaying Course Modules.'
                });
            });
        }
    };
});
