let bg;
let bgObjs = [];

let playerY = 330;
let jump = false;
let jumpPower = 15;
let groundLvl = 330;
let speed = 3.5;

let rockImg;
let rocks = [];

function preload() {
    bg = loadImage("assets/runner/rockland.png");
    rockImg = loadImage("assets/runner/rock.png");
}

function setup() {
    createCanvas(700, 500);

    for (let i = 0; i < 2; i++) {
        let temp = new BGImg(i * 700);
        bgObjs.push(temp);
    }

    for (let i = 0; i < 3; i++) {
        let temp = new Rock(width + i * 500);
        rocks.push(temp);
    }
}

function draw() {
    for (let b of bgObjs) {
        b.display();
        b.move();
    }

    for (let r of rocks) {
        r.display();
        r.move();
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
    jumpPower -= 0.5;
    
    if (playerY >= groundLvl) {
        playerY = groundLvl;
        jump = false;
        jumpPower = 15;
    }
}

class BGImg {
    constructor(x) {
        this.img = bg;
        this.x = x;
        this.speed = speed;
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
            speed += 0.2;
        }
    }
}


class Rock {
    constructor(x) {
        this.img = rockImg;
        this.x = x;
        this.y = groundLvl;
        this.speed = speed;
    }

    display() {
        image(this.img, this.x, this.y, 100, 100);
    }

    reset() {
        this.x = random(width, width + 2100);
    }

    move() {
        this.x -= this.speed;

        if (this.x < -80) {
           this.reset();
        }
    }
}