class Stopwatch {
    constructor(display, rleft, rright) {
        this.running = false;
        this.display = display;
        this.rleft = rleft;
        this.rright = rright;
        this.laps = [];
        this.reset();
        this.print(this.times);
        this.cl = 0;
        this.cr = 0;
    }
    
    reset() {
        this.times = [ 0, 0, 0];
    }
    
    start() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
    }
    
    lapse() {
        let times = this.times;
        // if (this.running) {
        //     this.reset();
        // }
        let li = document.createElement('li');
        li.innerText = this.format(times);
        // this.results.insertBefore(li, this.results.childNodes[0]);
        alert(times);
    }
    
    lapsel() {
        let times = this.times;
        this.cl += 1;
        let li = document.createElement('li');
        li.innerText = "Query " + this.cl + " finished at " + this.format(times);
        this.rleft.insertBefore(li, this.rleft.childNodes[0]);
        if (this.cl == 5) {
            start_mysql(0);
        } else {
            start_redis(this.cl);
        }
    }
    
    wlapsel() {
        let times = this.times;
        this.cl += 1;
        let li = document.createElement('li');
        li.innerText = "Query " + this.cl + " failed at " + this.format(times);
        li.className = "wrong";
        this.rleft.insertBefore(li, this.rleft.childNodes[0]);
        if (this.cl == 5) {
            start_mysql(0);
        } else {
            start_redis(this.cl);
        }
    }
    
    lapser() {
        let times = this.times;
        this.cr += 1;
        let li = document.createElement('li');
        li.innerText = "Query " + this.cr + " finished at: " + this.format(times);
        this.rright.insertBefore(li, this.rright.childNodes[0]);
        if (this.cr == 5) {
            stop();
        } else {
            start_mysql(this.cr);
        }
    }
    
    wlapser() {
        let times = this.times;
        this.cr += 1;
        let li = document.createElement('li');
        li.innerText = "Query " + this.cr + " failed at: " + this.format(times);
        li.className = "wrong";
        this.rright.insertBefore(li, this.rright.childNodes[0]);
        if (this.cr == 5) {
            stop();
        } else {
            start_mysql(this.cr);
        }
    }
    
    stop() {
        this.running = false;
        this.time = null;
    }

    restart() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
        this.reset();
    }
    
    clear() {
        clearChildren(this.results);
    }
    
    step(timestamp) {
        if (!this.running) return;
        this.calculate(timestamp);
        this.time = timestamp;
        this.print();
        requestAnimationFrame(this.step.bind(this));
    }
    
    calculate(timestamp) {
        var diff = timestamp - this.time;
        // Hundredths of a second are 100 ms
        this.times[2] += diff / 10;
        // Seconds are 100 hundredths of a second
        if (this.times[2] >= 100) {
            this.times[1] += 1;
            this.times[2] -= 100;
        }
        // Minutes are 60 seconds
        if (this.times[1] >= 60) {
            this.times[0] += 1;
            this.times[1] -= 60;
        }
    }
    
    print() {
        this.display.innerText = this.format(this.times);
    }
    
    format(times) {
        return `\
${pad0(times[0], 2)}:\
${pad0(times[1], 2)}:\
${pad0(Math.floor(times[2]), 2)}`;
    }
}

function pad0(value, count) {
    var result = value.toString();
    for (; result.length < count; --count)
        result = '0' + result;
    return result;
}

function clearChildren(node) {
    while (node.lastChild)
        node.removeChild(node.lastChild);
}

let stopwatch = new Stopwatch(
    document.querySelector('.stopwatch'),
    document.querySelector('#left_list'),
    document.querySelector('#right_list'));

function start(argument) {
    document.getElementById('spinner').className = "loader";
    stopwatch.start();
    start_redis(0);
    document.getElementById('left_list').innerText = "";
    document.getElementById('right_list').innerText = "";
    stopwatch.cr = 0;
    stopwatch.cl = 0;
}

function stop(argument) {
    document.getElementById('mysql').className = "";
    document.getElementById('vs').className = "";
    document.getElementById('redis').className = "";
    document.getElementById('running').className = "hide";
    document.getElementById('spinner').className = "loader hide";
    stopwatch.stop();
}

function start_mysql(query) {
    if (query == 0) {
        stopwatch.restart();
        document.getElementById('mysql').className = "hide";
        document.getElementById('vs').className = "hide";
        document.getElementById('redis').className = "hide";
        document.getElementById('running').className = "";
        document.getElementById('running').innerText = "MySQL";
    }

    if (query >=0 && query < 5) {
        var target = "php/mysql/mysql_question_" + (query + 1) + ".php";

        $.get(target, function(data, status){
            if (data == 1) {
                stopwatch.lapser();
            } else {
                stopwatch.wlapser();
            }
        });
    }
}

function start_redis(query) {
    if (query == 0) {
        stopwatch.restart();
        document.getElementById('mysql').className = "hide";
        document.getElementById('vs').className = "hide";
        document.getElementById('redis').className = "hide";
        document.getElementById('running').className = "";
        document.getElementById('running').innerText = "Redis";
    }
    if (query >=0 && query < 5) {
        var target = "php/redis/redis_question_" + (query + 1) + ".php";

        $.get(target, function(data, status){
            if (data == 1) {
                stopwatch.lapsel();
            } else {
                stopwatch.wlapsel();
            }
        }); 
    }
}
