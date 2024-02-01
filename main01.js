const CLIENT_ID = '928580067145-b9ub9jr25h1k9dejl47gak2nvjjqsu4c.apps.googleusercontent.com';
const API_KEY = 'AIzaSyDxk9JJ5BL_7gtEGJFNPRIGLo3qEOTsApk';
const SPREADSHEET_ID = '1Di6ReUjyV6OO4oaLZKTomfKgguvqn8SATu6lV0huZxM';
const SHEET_ID = 'Consolidated';
const SCOPES = 'https://www.googleapis.com/auth/spreadsheets.readonly';

let tokenClient;
let gapiInited = false;
let gisInited = false;

window.onbeforeunload = closingCode;

function gapiLoaded() {
    gapi.load('client', initializeGapiClient);
}
async function initializeGapiClient() {
    await gapi.client.init({
        apiKey: API_KEY,
    });
    gapiInited = true;
    maybeEnableButtons();
}
function gisLoaded() {
    tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CLIENT_ID,
        scope: SCOPES,
        callback: '', // defined later
    });
    gisInited = true;
    maybeEnableButtons();
}
function maybeEnableButtons() {
    if (gapiInited && gisInited) {
        document.getElementById('authorize_button').style.visibility = 'visible';
    }
}
function handleAuthClick() {
    tokenClient.callback = async (resp) => {
        if (resp.error !== undefined) {
        throw (resp);
        }
        document.getElementById('signout_button').style.visibility = 'visible';
        document.getElementById('authorize_button').innerText = 'Refresh';
        await listMajors();
    };

    // if (gapi.client.getToken() === null) {
    //   // Prompt the user to select a Google Account and ask for consent to share their data
    //   // when establishing a new session.
    //   tokenClient.requestAccessToken({prompt: 'consent'});
    // } else {
        // Skip display of account chooser and consent dialog for an existing session.
        tokenClient.requestAccessToken({prompt: ''});
    // }
}
function closingCode() {
    const token = gapi.client.getToken();
    if (token !== null) {
        google.accounts.oauth2.revoke(token.access_token);
        gapi.client.setToken('');
        document.getElementById('content').innerText = '';
    }
}
//   async function listMajors() {
//     let response;
//     try {
//       response = await gapi.client.sheets.spreadsheets.values.get({
//         spreadsheetId: SPREADSHEET_ID,
//         range: `${SHEET_ID}!C4:I10`,
//       });
//     } catch (err) {
//       document.getElementById('content').innerText = err.message;
//       return;
//     }
//     const range = response.result;
//     if (!range || !range.values || range.values.length == 0) {
//       document.getElementById('content').innerText = 'No values found.';
//       return;
//     }
//     // Flatten to string to display
//     const output = range.values.reduce(
//         (str, row) => `${str}${row[0]}, ${row[4]}\n`,
//         'Name, Major:\n');
//     document.getElementById('content').innerText = output;
//   }
async function checkCertificate(range, valueInputOption, _values, callback) {
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
            spreadsheetId: SPREADSHEET_ID,
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