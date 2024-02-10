const gsh = {
    data() {
        return {
            // Application constants
            COMPANY_NAME  : 'Ustaz PRO',
            // Google API constants
            CLIENT_ID     : '928580067145-b9ub9jr25h1k9dejl47gak2nvjjqsu4c.apps.googleusercontent.com',
            API_KEY       : 'AIzaSyDxk9JJ5BL_7gtEGJFNPRIGLo3qEOTsApk',
            SPREADSHEET_ID: '1Di6ReUjyV6OO4oaLZKTomfKgguvqn8SATu6lV0huZxM',
            SHEET_ID      : 'Consolidated',
            SCOPES        : 'https://www.googleapis.com/auth/spreadsheets.readonly',
            // Google API variables
            tokenClient   : null,
            gapiInited    : false,
            gisInited     : false,
        }
    },
    computed: {
        isInteractionAllowed() {
            return this.gapiInited && this.gisInited
        },
    },
    methods: {
        gapiLoaded() {
            gapi.load( 'client', this.initializeGapiClient );
        },
        async initializeGapiClient() {
            await gapi.client.init({
                apiKey: this.API_KEY,
            });
            this.gapiInited = true;
        },
        gisLoaded() {
            this.tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: this.CLIENT_ID,
                scope    : this.SCOPES,
                callback : '', //defined later
            });
            this.gisInited = true;
        },
        updateValues(range, valueInputOption, _values, callback) {
            let values = [
                [
                // Cell values ...
                ],
                // Additional rows ...
            ];
            values = _values;
            const body = {
                values: values,
            };
            try {
                gapi.client.sheets.spreadsheets.values.update({
                spreadsheetId: this.SPREADSHEET_ID,
                range: range,
                valueInputOption: valueInputOption,
                resource: body,
                }).then((response) => {
                const result = response.result;
                console.log(`${result.updatedCells} cells updated.`);
                if (callback) callback(response);
                });
            } catch (err) {
                document.getElementById('content').innerText = err.message;
                return;
            }
        }
    },
    created() {
        this.gisLoaded()
        this.gapiLoaded()
    }
}