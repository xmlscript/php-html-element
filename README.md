# PHP HTML Element

Create and modify HTML tags in PHP the simple way with OOP classes

## Examples

### Simple Tags

    use WernerFreytag\HTML;
    
    $link = new Tag('a');
    $link->href = 'http://www.google.com';
    $link->setText('Google this!');
    
    echo $link->render();
    
Output:

    <a href="http://www.google.com">Google this!</a>
    
### Tag hierarchy

    use WernerFreytag\HTML;
    
    $data = array( array('1st row, column 1', '1st row, column 2'), array('2nd row, column 1', '2nd row, column 2') );
    
    $table = new Tag('table');
    
    foreach ( $data as $row )
    {
        $tr = new Tag('tr');
        $table->addChild( $tr );
        
        foreach ( $row as $cell )
        {
            $td = new Tag('td');
            $td->setText( $cell );
            
            $tr->addChild( $td );
        }
    }
    
    echo $table;
    
Output:

    <table><tr><td>1st row, column 1</td><td>1st row, column 2</td></tr><tr><td>2nd row, column 1</td><td>2nd row, column 2</td></tr></table>

