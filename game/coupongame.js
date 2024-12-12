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

let myScore = 0;
let aiScore = 0;

let evalTimer = 100;
let statScreen = false;
let outcome = "";

let trash = [];
let discarding = false;
let recycling = false;
let recycleTimer = 100;
let gameReset = false;
let inCorner = false;
let anotherBool = false;
let resetTimer = 50;
let lmao = false;

let consecutiveWins = 0;

function preload() {
    rockSprite = loadImage("assets/coupon/rat.png");
    paperSprite = loadImage("assets/coupon/cat.png");
    scissorSprite = loadImage("assets/coupon/dog.png");
    cardbackSprite = loadImage("assets/coupon/cardback.png");
}

function setup() {
    createCanvas(800, 600);

    for (let i = 0; i < 8; i++) {
        cards.push(new Card(rockSprite));
    }

    for (let i = 0; i < 8; i++) {
        cards.push(new Card(paperSprite));
    }

    for (let i = 0; i < 8; i++) {
        cards.push(new Card(scissorSprite));
    }

    Shuffle();
    Deal();
}

function draw() {
   // background(238,192,203);
   background(200);

    fill(255);
    textSize(40);
    textAlign(LEFT);
    text(consecutiveWins + "/5", 20, 50);
    //text(aiScore, width - 50, 50);

    for (let c of cards) {
        c.display();
        if (c.moving) {
            c.checkMove();
        }
    }

    for (let c of AI) {
        c.display();
        if (c.moving) {
            c.checkMove();
        }
    }

    for (let c of trash) {
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

    if(statScreen) {
        textSize(28);
        textAlign(CENTER);
        text(outcome, width/1.88, height/1.47);
        evalTimer--;
    }

    if (evalTimer <= 0) {
        statScreen = false;
        discard();
        evalTimer = 100;
    }

    if(discarding) {
        // console.log(discarding);
        if (cards.length > 0) {
            for (let i = 0; i < trash.length; i++) {
                if (trash[i].moving) {
                    return;
                }
            } 
            Deal();
        } else {
            recycling = true;
        }
        discarding = false;
     }

     if (recycling) {
        recycleTimer--;
            if (recycleTimer <= 0) {
                recycle();
                recycleTimer = 100;
                recycling = false;
            }
     }

     if (gameReset) {
        resetting();
     }

     if(lmao) {
        resetTimer--;
            if (resetTimer <= 0) {
                Deal();
                lmao = false;
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
                evaluate();
            }

            if (trash.includes(this) && trash.indexOf(this) == 23 && inCorner) {
                gameReset = true;
                inCorner = false;
            }

            if (anotherBool && cards.indexOf(this) == 23) {
                lmao = true;
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
        temp.delay = 10 + i * 10;
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
        temp.delay = 10 + (i+3)*10;
        temp.moving = true;

        player.push(temp);
        cards.pop(temp);
    }

    //console.log(player);
}

function AIPlay() {
    let selected = AI[Math.floor(Math.random() * AI.length)];
   // console.log(selected);
    selected.targetX = width/1.6;
    selected.targetY = height/2;
    selected.moving = true;
    selected.AICard = true;
    pool.push(selected.face);
   // console.log(AI);
}

function evaluate() {
    let pCard = pool[0];
    let aCard = pool[1];

    for (let c of AI) {
        c.inHand = true;
    }

    if (pCard == rockSprite && aCard == paperSprite ||
        pCard == paperSprite && aCard == scissorSprite ||
        pCard == scissorSprite && aCard == rockSprite
    ) {
        aiScore += 1;
        outcome = "Lose";
        consecutiveWins = 0
    } else if (aCard == rockSprite && pCard == paperSprite ||
        aCard == paperSprite && pCard == scissorSprite ||
        aCard == scissorSprite && pCard == rockSprite) {
            myScore += 1;
            outcome = "Win";
            consecutiveWins += 1;
            if (consecutiveWins === 1) {
                awardCoupon();
            }
        } else {
            outcome = "Draw";
        }
 
    statScreen = true;

}

function discard() {
    discarding = true;
    pool = [];

    for (let i = 0; i < 3; i++) {
        let c = AI[AI.length -1];
       // console.log(c);
        c.targetX = width/10 * 9;
        c.targetY = height/2;
        c.delay = 10 + i*10;
        c.moving = true;
        c.AICard = false;
        trash.push(c);
        AI.pop(c);
    }

    for (let i = 0; i < 3; i++) {
        let c = player[player.length -1];
        c.targetX = width/10 * 9;
        c.targetY = height/2;
        c.delay = 10 + (i+3)*10;
        c.moving = true;
        trash.push(c);
        player.pop(c);
    }

    inPlay = false;
}

function recycle() {
//console.log("recycling");
inCorner = true;
    for (let i = 0; i < 24; i++) {
        let temp = trash[i];
        temp.delay = 10 + i * 3;
        temp.inHand = false;
        temp.targetX = width/10;
        temp.targetY = 100;
        temp.moving = true;
    }
}

function resetting() {
    for (let i = trash.length - 1; i >= 0; i--) {
        let temp = trash[i];
        cards.push(temp);
        trash.pop(temp);
    }
     console.log(cards);

    for (let i = cards.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [cards[i], cards[j]] = [cards[j], cards[i]];
      }

      bruh();
      gameReset = false;
}

function bruh() {
    //not working
    for (let i = 0; i < cards.length; i++) {
        let temp = cards[i];
        temp.targetX = width/10;
        temp.targetY = 450 - cards.indexOf(temp) * sep;
        temp.moving = true;
       // temp.delay = 10 + i * 10;
       // console.log(temp.x, temp.y);
    }
    resetTimer = 50;
    anotherBool = true;
}

function awardCoupon() {
    let couponCode = 'COUPON-' + Math.random().toString(36).substring(2, 10).toUpperCase();

    //send coupon code to server
    fetch('couponGenerator.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ coupon: couponCode })
    }).then(response => {
        if (response.ok) {
            alert('Congratulations! You earned a coupon: ' + couponCode);
        } else {
            alert('Failed to save coupon. Please try again later.');
        }
    });

    consecutiveWins = 0; 
}