const config = require('./config.js')
const mysql = require('mysql')
//const payutc = require('../payutc/payutc.js')
const XmlHttpRequest = require('xhr2')
const bodyParser = require('body-parser');
const moment = require('moment')
const cors = require('cors')


/*const mySqlConnection = mysql.createConnection({
  host: config.BDD_HOST,
  user: config.BDD_USERNAME,
  password: config.BDD_PASSWORD,
  database: config.BDD
});*/

var exports = module.exports = {
  insert : {},
  select : {}
}


//##############################################################################################
// FONCTIONS D INSERTION DANS LA BASE
//##############################################################################################


//fonction de création d'un utilisateur
/*exports.insert.createUser = (lastname, firstname, email, password, success, error, isAdult = 1, login=null)=>{
  exports.select.getUserByEmail(email, password, (data)=>{
    if(data.length>0)
    {
      success(data)
    }
    else
    {
      const insertQuery = "insert into Users (login,lastname,firstname,email, password, isAdult) values ("+mySqlConnection.escape(login)+","+mySqlConnection.escape(lastname)+","+mySqlConnection.escape(firstname)+","+mySqlConnection.escape(email)+",SHA("+mySqlConnection.escape(password)+"),"+mySqlConnection.escape(isAdult)+")";
      mySqlConnection.query(
        insertQuery,
        (err,result)=>{
          if(err)error(err)
          else success(result)
        })
    }
  }, (err)=>{if(err)error(err)})
}


exports.select.create



//##############################################################################################
// FONCTIONS DE SELECTION DANS LA BASE
//##############################################################################################


//fonction de reccupération d'un utilisateur par son user_mail
exports.select.getUserByEmail =(email, password, success, error)=>{
  const selectQuery = "select * from Users where email="+mySqlConnection.escape(email)+" and password = SHA("+mySqlConnection.escape(password)+")"
  mySqlConnection.query(
    selectQuery,
    (err,result)=>{
      if(err)error(err)
      else success(result)
    })
}

//fonction de reccupération d'un utilisateur par son login
exports.select.getUserByLogin =(login, password, success, error)=>{
  const selectQuery = "select * from Users where login="+mySqlConnection.escape(login)+" and password = SHA("+mySqlConnection.escape(password)+")"
  mySqlConnection.query(
    selectQuery,
    (err,result)=>{
      if(err)error(err)
      else success(result)
    })
}*/
