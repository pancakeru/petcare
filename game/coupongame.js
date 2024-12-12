let rockSprite;
let paperSprite;
let scissorSprite;
let cardbackSprite;

let cards = [];
let sep = 15;

let AI = [];
let player = [];
let inPlay = false;
let pool = [];

function preload() {
    rockSprite = loadImage("assets/coupon/rockcard.png");
    paperSprite = loadImage("assets/coupon/papercard.png");
    scissorSprite = loadImage("assets/coupon/scissorscard.png");
    cardbackSprite = loadImage("assets/coupon/cardback.png");
}

function setup() {
    createCanvas(800, 600);

    for (let i = 0; i < 7; i++) {
        cards.push(new Card(rockSprite));
    }

    for (let i = 0; i < 7; i++) {
        cards.push(new Card(paperSprite));
    }

    for (let i = 0; i < 7; i++) {
        cards.push(new Card(scissorSprite));
    }

    Shuffle();
    Deal();
}

function draw() {
   // background(238,192,203);
   background(200);

    for (let c of cards) {
        c.display();
    }

    for (let c of AI) {
        c.display();
        if (c.moving) {
            c.checkMove();
        }
    }

    for (let c of player) {
        c.display();
        if (c.moving) {
            c.checkMove();
        }

        if(c.inHand) {
            c.hover();
        }
    }
}

class Card {
    constructor(face) {
        this.x = 0;
        this.y = 0;
        this.back = cardbackSprite;
        this.face = face;

        this.inHand = false;
        this.discard = false;
        this.reveal = false;
        this.AICard = false;

        this.height = 160;
        this.width = 120;

        this.targetX = 0;
        this.targetY = 0;
        this.speed = 13;
        this.moving = false;
        this.delay = 10;
        this.up = false;
        this.right = false;
    }

    display() {
        imageMode(CENTER);

        if (!this.reveal && !this.inHand) {
            image(this.back, this.x, this.y, this.width, this.height);
        } else {
            image(this.face, this.x, this.y, this.width, this.height);
        }
    }

    checkMove() {
        this.delay--;
        //console.log(dist(this.x, this.y, this.targetX, this.targetY));

        if (this.delay <= 0) {
                if (this.x < this.targetX) {
                    this.right = true;
                } else {
                    this.right = false;
                }
    
                if (this.y < this.targetY) {
                    this.up = false;
                } else {
                    this.up = true;
                }
            this.move();
        }

    }

    move() {
        if (this.right) {
                this.x += this.speed;
                this.x = constrain(this.x, this.x, this.targetX);
            } else {
                this.x -= this.speed;
                this.x = constrain(this.x, this.targetX, this.x);
            }

        if (!this.up) {
                this.y += this.speed/1.5;
                this.y = constrain(this.y, this.y, this.targetY);
            } else {
                this.y -= this.speed/1.5;
               this.y = constrain(this.y, this.targetY, this.y);
            }
        
        if(this.x == this.targetX && this.y == this.targetY) {
            if (player.includes(this)) {
                this.inHand = true;
            }

            if(this.AICard) {
                this.inHand = true;
            }

            this.moving = false;
        }
    }

    hover() {
        if (dist(mouseX, mouseY, this.x, this.y) <= 50 && !inPlay) {
            this.height = 192;
            this.width = 144;

            if(!inPlay) {
                if (mouseIsPressed) {
                    this.targetX = width/2.3;
                    this.targetY = height/2;
                    this.checkMove();
                    this.moving = true;
                    pool.push(this.face);
                    AIPlay();
                    inPlay = true;
                }
            }

        } else {
            this.height = 160;
            this.width = 120;
        }
    }

}

function Shuffle() {
    for (let i = cards.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [cards[i], cards[j]] = [cards[j], cards[i]];
      }
    
    for (let c of cards) {
        c.x = width/10;
        c.y = 450 - cards.indexOf(c) * sep;
    }
}

function Deal() {
    for (let i = 0; i < 3; i++) {
        let temp = cards[cards.length - 1];
        temp.targetX = width/4 * (i/1.5 + 1) + 110;
        temp.targetY = height/6;
        temp.delay += i*temp.delay;
        temp.moving = true;
        temp.inAI = true;
       // console.log(temp);
        AI.push(temp);
        cards.pop(temp);
    }

    for (let i = 0; i < 3; i++) {
        let temp = cards[cards.length - 1];
        temp.targetX = width/4 * (i/1.5 + 1) + 110;
        temp.targetY = height/6 * 5;
        temp.delay += (i+3)*temp.delay;
        temp.moving = true;

        player.push(temp);
        cards.pop(temp);
    }
}

function AIPlay() {
    let selected = AI[Math.floor(Math.random() * AI.length)];
   // console.log(selected);
    selected.targetX = width/1.6;
    selected.targetY = height/2;
    selected.moving = true;
    selected.AICard = true;
    pool.push(selected.face);
}