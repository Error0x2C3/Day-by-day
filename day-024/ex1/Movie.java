package labo8.ex1;
import java.time.LocalDate;
class Movie {
    private final String title;
    private final int releaseYear; // l'année de sortie.
    /*
    un booléen indiquant si on peut regarder le film en streaming ou seulement en location
    (VOD).
     */
    private final boolean streamable;
    // un booléen indiquant si le film est une avant-première
    private final boolean isPremiere;

    public Movie(String title, int releaseYear, boolean isPremiere, boolean streamable) {
        this.title = title;
        this.releaseYear = releaseYear;
        this.isPremiere = isPremiere;
        this.streamable = streamable;
    }

    public String getTitle() {
        return title;
    }

    public int getReleaseYear() {
        return releaseYear;
    }

    public boolean isPremiere() {
        return isPremiere;
    }

    public boolean isStreamable() {
        return streamable;
    }


    public boolean isOld(){
        // Les films datant de plus de 5 ans sont considérés comme anciens;
        int annee = LocalDate.now().getYear(); // Année actuelle.
        if(this.releaseYear > (annee-5)){
            return false;
        }
        return true;
    }

    @Override
    public String toString(){
        String format = "";
        format += "Nom du film : " + this.getTitle();
        format += "\n";
        format += "Année de sortie : "+this.getReleaseYear();
        return format;
    }
}
