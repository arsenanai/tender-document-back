export default class User {
    constructor(){}
    email;
    password;
    token;
    fillables = [
        {
            name: 'email',
            type: 'string',
            required: true,
        },
        {
            name: 'password',
            type: 'password',
            required: true,
        },
        {
            name: 'name',
            type: 'string',
            required: true,
        }
    ];
}