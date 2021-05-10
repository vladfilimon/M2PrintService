const http = require("http");
const fs = require("fs");

const img = fs.readFileSync("./src/output.png");

//create a server object:
http
    .createServer(function (req, res) {
        res.writeHead(200, { "Content-Type": "image/jpg" });
        res.end(img, "binary");
    })
    .listen(8080); //the server object listens on port 8080
