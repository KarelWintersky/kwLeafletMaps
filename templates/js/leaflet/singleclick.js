L.Map.addInitHook( function () {
    
    var that = this
    ,   h
    ;
    
    if (that.on)
    {
        that.on( 'click',    check_later );
        that.on( 'dblclick', function () { setTimeout( clear_h, 0 ); } );
    }
    
    function check_later( e )
    {
        clear_h();
        
        h = setTimeout( check, 500 );
        
        function check()
        {
            that.fire( 'singleclick', L.Util.extend( e, { type : 'singleclick' } ) );
        }
    }
    
    function clear_h()
    {
        if (h != null)
        {
            clearTimeout( h );
            h = null;
        }
    }
    
});
