export default class Partner
{
    constructor(){}
    id;
    name;
    subpartners;
    createdAt;
    updatedAt;
    fillables = [
        {
            name: 'id',
            type: 'number',
            required: true,
        },
        {
            name: 'name',
            type: 'string',
            required: true,
        },
        {
            name: 'createdAt',
            type: 'date',
        },
        {
            name: 'updatedAt',
            type: 'date',
        }
    ];
    hasMany = {
        name: 'subpartners',
        entity: 'Subpartner'
    };
}