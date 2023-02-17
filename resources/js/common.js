export const common = {
	data(){
		return {
			user: null,
		}
	},
	methods:{
		/*display(what,target){
			var result = ''
			if(target!==null)
		      if(what==='name')
		          result = target['name_'+this.$i18n.locale]
		      else if(what==='description')
		          result = target['description_'+this.$i18n.locale]
		    //if(this.stringIsSet(result) &&result.length>100)
		    //	result = result.substring(0,75)+' ... '+result.substring(result.length-25,result.length)
		   	return result
	    },
	    view(what,target){
			var result = ''
			if(target!==null)
		      if(what==='name')
		          result = target['name_'+this.$i18n.locale]
		      else if(what==='description')
		          result = target['description_'+this.$i18n.locale]
		    if(this.stringIsSet(result) &&result.length>100)
		    	result = result.substring(0,75)+' ... '+result.substring(result.length-25,result.length)
		   	return result
	    },*/
	    logout(){
	    	this.$router.push({name:'auth.logout'})
	    },
	    basicErrorHandling(e){
	    	console.log(e.stack)
	    	if(e.response)
	    		if(e.response.status)
            		if(e.response.status==401)
                		this.logout()
                	else if(e.response.status==403)
                		return this.toast('warning', "You don't have permission")
                	else if(e.response.status==500)
                		return this.toast('danger', 'Some error occured. Contact support')
            return this.toast('danger', 'Try again later')
	    },
	    toast(type,text){
	    	var message = {}
            message.type="alert alert-"+type
            message.text = text
            return message;
	    },
	    /*getType(type){
            if(type.startsWith('migrate_'))
                return type.split('_')[1]
            else if(type.startsWith('code_')
            		||type.startsWith('subgroup_')
            		||type.startsWith('group_')
            	)
                return type.split('_')[0]
            else
                return type
        },*/
        stringIsSet(string){
        	return (string!=null && string != '')
        },
        arrayIsSet(array){
        	return (array!=null && array.length>0)
        },
        /*is(role){
        	if(role=='admin')
        		if(localStorage.getItem('dictionary_user_email')!=null && localStorage.getItem('dictionary_user_email')=='ensuser@skc.kz')
            		return true
           	return false
        },*/
        authenticated() {
            return this.user !== null;
        }
	}
}