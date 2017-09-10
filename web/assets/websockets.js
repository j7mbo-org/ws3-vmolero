ab.connect('ws://localhost:1338', function(session) {

    $('#subscribe-time').on('click', function(e) {
        e.preventDefault();
        $('#subscribe-time, #unsubscribe-time').toggleClass('disabled');

        session.subscribe('time', function(topic, data) {
            $('#data-time').html(data);
        });
    });

    $('#unsubscribe-time').on('click', function(e) {
        e.preventDefault();
        $('#subscribe-time, #unsubscribe-time').toggleClass('disabled');

        session.unsubscribe('time');
    });

    // @todo Do the same for 'sql' topic by using the DOM elements: #subscribe-sql, #unsubscribe-sql and #data-sql
});
