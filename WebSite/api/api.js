
const config = require('./config.js')
const mysql = require('mysql')
//const payutc = require('../payutc/payutc.js')
const XmlHttpRequest = require('xhr2')
const bodyParser = require('body-parser');
const moment = require('moment')
const cors = require('cors')
const express = require('express')
const app = express()
const request = require('request')

const join = require('path').join
const mustacheExpress = require('mustache-express')
app.use(cors())
//app.use(express.static('public'))
app.use(bodyParser.json())
app.use(bodyParser.urlencoded({extended:true}))




const mycalls = require('./callFunctions.js')

app.engine('html', mustacheExpress())
app.set('view engine', 'html')
app.use(express.static(join(__dirname,'../public')))

app.set('views',join(__dirname,'../views'))

app.get('/', (req,res)=>{
  res.render('./index.html')
})

app.get('/test', (req,res)=>{
  res.redirect('./html/test.html')
})

app.listen(3000, ()=>{console.log('app listening on port 3000')})
/*
mycalls.insert.createUser('richard','quentin','quentin.richard@etu.utc.fr', 'quentinrichard94' ,
(data)=>{
  if(data)console.log(data)

},
(err)=>{
  if(err)console.error(err)
},1,'qrichard')
*/

//mycalls.select.getUserByLogin('qrichard', 'quentinrichard94', (data)=>{console.log(data)}, (err)=>{console.log(err)})
