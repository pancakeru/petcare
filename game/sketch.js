let bg;
let bgObjs = [];

let playerY = 330;
let jump = false;
let jumpPower = 20;
let groundLvl = 330;

function preload() {
    bg = loadImage("assets/runner/rockland.png");
}

function setup() {
    createCanvas(700, 500);

    for (let i = 0; i < 2; i++) {
        let temp = new BGImg(i * 700);
        bgObjs.push(temp);
    }
}

function draw() {
    for (let b of bgObjs) {
        b.display();
        b.move();
    }

    rect(width/10, playerY, 80, 80);

    if (jump) {
        Jump();
    }
}

function keyPressed() {
    if (key == ' ' && !jump) {
        jump = true;
    }
}

function Jump() {
    playerY -= jumpPower;
    jumpPower -= 1;
    
    if (playerY >= groundLvl) {
        playerY = groundLvl;
        jump = false;
        jumpPower = 20;
    }
}

class BGImg {
    constructor(x) {
        this.img = bg;
        this.x = x;
        this.speed = 2;
        this.y = 0;
    }

    display() {
        image(this.img, this.x, this.y, 700, 500);
    }

    move() {
        this.x -= this.speed;

        if (this.x < -700) {
            this.x = 700;
        }

        if (frameCount % 200 == 0 && this.speed < 8) {
            this.speed += 0.2;
        }
    }
}