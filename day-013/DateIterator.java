package Ex2;

import Ex2.Date;
import Ex2.DateRange;

import java.util.Iterator;

class DateIterator implements Iterator< Date > {
    DateRange dr ;
    Date cour ;
    DateIterator ( DateRange dr ) {
        this . dr = dr ;
        cour = dr . deb ;
    }
    @Override public boolean hasNext () {
        return cour . compareTo ( dr . fin ) <= 0;
    }
    @Override public Date next () {
        /*
        Date res = cour ;
        cour.increment ();
        return res ;
        Le problème est que res retient est lié à la variable cours,
        au lieu de créer une nouvelle instance Date.
         */
        Date res = new Date(cour.getDay(),cour.getMonth(),cour.getYear());
        cour . increment ();
        return res ;
    }
}