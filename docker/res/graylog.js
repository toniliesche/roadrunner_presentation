db.createUser({
    user: 'root',
    pwd: 'phpughh',
    roles: [
        {
            role: 'root',
            db: 'admin',
        },
    ],
});
db.createCollection('graylog', { capped: false });

use graylog;
db.createUser({
    user: 'root',
    pwd: 'phpughh',
    roles: [
        {
            role: 'root',
            db: 'admin',
        },
    ],
});
