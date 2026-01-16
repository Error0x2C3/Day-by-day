package Ex2;

import java.util.Iterator;

class DateRange implements Iterable < Date > {
    Date deb , fin ;

    public DateRange ( Date deb , Date fin ) {
        this . deb = deb ;
        this . fin = fin ;
    }
    @Override public Iterator< Date > iterator () {
        return new DateIterator ( this );
    }
}