let Utils = {
    rand: function(min, max) 
    {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    convertTime: function( time ) 
    {
        let minutes = Math.floor(time / 60);
        let hours   = Math.floor(minutes / 60);
        minutes     = minutes - (hours * 60);

        if (hours < 10) {
            if (minutes < 10) 
                return '0' + hours + ':0' + minutes;
            else 
                return '0' + hours + ':' + minutes;
        } else {
            if (minutes < 10) 
                return hours + ':0' + minutes;
            else 
                return hours + ':' + minutes;
        }
    }
}

export {Utils};