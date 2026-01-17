package pratique;

import java.time.LocalDate;

public interface Eatable {
    public LocalDate datePeremption();
    public int compareTo( Eatable e);
}
