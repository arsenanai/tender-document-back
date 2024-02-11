const gsh = {
    data() {
        return {
            SPREADSHEET_ID: '1H1OefSrhdwBwwr9cWeXIXOBRmw9TErt4mrKxj2My8p0',
            sheets        : [1,2,4,5,6,7,8],
            controller    : new AbortController(),
            answers       : [],
            response      : null,
        }
    },
    methods: {
        async searchData() {
            let j = 0
            while (j < this.sheets.length) {
                fetch(`https://opensheet.elk.sh/${this.SPREADSHEET_ID}/${this.sheets[j]}`,{signal: this.controller.signal}).then((response) => {
                    this.handleRequest(j,response)
                }).catch( ( error ) => {
                    console.log('can be ignored', error)
                });
                j++
            }
            return null
        },
        async handleRequest(index, response){
            response = await response.json()
            // console.log('response', response)
            let i = 0
            while (i < response.length) {
                // console.log('row', response[1][i])
                if( response[i]['Номер сертификата'] === this.certificate ) {
                    console.log('found entry', response[i])
                    this.state = 'success'
                    this.response = response[i]
                    this.controller.abort()
                    return
                }
                i++
            }
            this.answers[index] = 'not_found'
        },
        async fetchDataFromSingleSheet( sheetNumber ) {
            const url = `https://opensheet.elk.sh/${this.SPREADSHEET_ID}/${sheetNumber}`
            const response = fetch( url, { signal: this.controller.signal } )
            const body = await response.json()
            return body
        },
        notFoundEverywhere() {
            return this.answers.length >= this.sheets.length
        }

        // controller.abort();
    },
}