const express = require('express')
fs.readFile(file
const app = express()
const port = 3000
const sharp = require('sharp');
//app.use(express.bodyParser());


app.post('/', (req, res) => {
    //var buf = new Buffer(req.body.toString('binary'),'binary');
    res.send(sharp(req.body)
        .rotate()
        .resize(200)
        .jpeg({ mozjpeg: true })
        .toBuffer())
})

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
})
