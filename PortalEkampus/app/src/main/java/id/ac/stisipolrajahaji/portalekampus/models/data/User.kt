package id.ac.stisipolrajahaji.portalekampus.models.data

data class User (
    var userid:Int,
    var idbank:Int,
    var username:String,
    var userpassword:String,
    var salt:String,
    var page:String,
    var group_id:Int,
    var kjur:Int,
    var email:String,
    var active:Boolean,
    var isdeleted:Boolean,
    var theme:String,
    var foto:String,
    var token:String,
    var ipaddress:String,
    var logintime:String,
    var date_added:String
)