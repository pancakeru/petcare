let bg;
let bgObjs = [];

let playerY = 330;
let jump = false;
let jumpPower = 15;
let groundLvl = 330;
let speed = 3.5;

let rockImg;
let rocks = [];

let pause = true;
let endScreen = false;
let startScreen = true;

let score = 0;

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
    }

    textAlign(CENTER);

    if(startScreen) {
        textSize(80);
        text("Desert Run", width/2, height/2.2);
        textSize(30);
        text("'Space' to start", width/2, height/1.8);
    }

    fill(255);

    if (!pause) {
        for (let b of bgObjs) {
            b.display();
            b.move();
        }
    
        for (let r of rocks) {
            r.display();
            r.move();
            r.collision();
        }

        fill(0);
        textSize(40);
        text(score, width-40, 50);

        fill(255);
    }

    rect(width/10, playerY, 80, 80);

    if (jump) {
        Jump();
    }

    if (endScreen) {
        textSize(80);
        text("Game Over", width/2, height/2.2);
        textSize(30);
        text("Score: " + score, width/2, height/1.8);
    }
}

function keyPressed() {
    if (key == ' ' && !jump && !endScreen) {
        jump = true;
    }

    if (key == ' ' && pause && !endScreen) {
        pause = false;
        startScreen = false;
    }

    if (key == ' ' && pause && endScreen) {
        speed = 3.5;
        rocks = [];
        for (let i = 0; i < 3; i++) {
            let temp = new Rock(width + i * 500);
            rocks.push(temp);
        }
        pause = false;
        endScreen = false;
        score = 0;
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
        score += 1;
    }

    move() {
        this.x -= this.speed;

        if (this.x < -80) {
           this.reset();
        }
    }

    collision() {
        if (dist(width/10, playerY, this.x, this.y) <= 10) {
            pause = true;
            endScreen = true;
        }
    }
}