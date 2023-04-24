const api = '/api/';
export class Api {
    static async get ( uri ) {
        return await fetch( api+uri, {cache: "no-store"} )
            .then( response => response.json() );
    }
    static async post ( uri, params ) {
        return await fetch( api+uri, {
            method: "post",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify( params )
        } ).then(response => response.json());
    }
}
