package AVLDrevo;

public class AVLDrevo {
    private class Vozlisce {
        int kljuc, visina, stevec, velikost;
        Vozlisce levo, desno;

        Vozlisce(int kljuc) {
            this.kljuc = kljuc;
            this.visina = 1;
            this.stevec = 1;
            this.velikost = 1;
        }
    }

    private Vozlisce koren;

    public AVLDrevo() {
        koren = null;
    }

    private int visina(Vozlisce vozlisce) {
        return vozlisce == null ? 0 : vozlisce.visina;
    }

    private int velikost(Vozlisce vozlisce) {
        return vozlisce == null ? 0 : vozlisce.velikost;
    }

    private int ravnotezje(Vozlisce vozlisce) {
        return vozlisce == null ? 0 : visina(vozlisce.levo) - visina(vozlisce.desno);
    }

    private void posodobi(Vozlisce vozlisce) {
        if (vozlisce != null) {
            vozlisce.velikost = vozlisce.stevec + velikost(vozlisce.levo) + velikost(vozlisce.desno);
            vozlisce.visina = 1 + Math.max(visina(vozlisce.levo), visina(vozlisce.desno));
        }
    }

    private Vozlisce rotacijaDesno(Vozlisce y) {
        Vozlisce x = y.levo;
        Vozlisce T2 = x.desno;
        x.desno = y;
        y.levo = T2;
        posodobi(y);
        posodobi(x);
        return x;
    }

    private Vozlisce rotacijaLevo(Vozlisce x) {
        Vozlisce y = x.desno;
        Vozlisce T2 = y.levo;
        y.levo = x;
        x.desno = T2;
        posodobi(x);
        posodobi(y);
        return y;
    }

    private Vozlisce uravnotezi(Vozlisce vozlisce) {
        posodobi(vozlisce);
        int ravnotezje = ravnotezje(vozlisce);
        if (ravnotezje > 1) {
            if (ravnotezje(vozlisce.levo) < 0)
                vozlisce.levo = rotacijaLevo(vozlisce.levo);
            return rotacijaDesno(vozlisce);
        }
        if (ravnotezje < -1) {
            if (ravnotezje(vozlisce.desno) > 0)
                vozlisce.desno = rotacijaDesno(vozlisce.desno);
            return rotacijaLevo(vozlisce);
        }
        return vozlisce;
    }

    public void vstavi(int kljuc) {
        koren = vstavi(koren, kljuc);
    }

    private Vozlisce vstavi(Vozlisce vozlisce, int kljuc) {
        if (vozlisce == null) {
            return new Vozlisce(kljuc);
        }
        if (kljuc < vozlisce.kljuc) {
            vozlisce.levo = vstavi(vozlisce.levo, kljuc);
        } else if (kljuc > vozlisce.kljuc) {
            vozlisce.desno = vstavi(vozlisce.desno, kljuc);
        } else {
            vozlisce.stevec++;
            posodobi(vozlisce);
            return vozlisce;
        }
        return uravnotezi(vozlisce);
    }

    public void najdi(int kljuc) {
        najdi(koren, kljuc);
        System.out.println();
    }

    private void najdi(Vozlisce vozlisce, int kljuc) {
        if (vozlisce == null) {
            System.out.print("x");
            return;
        }
        System.out.print(vozlisce.kljuc + ",");
        if (kljuc < vozlisce.kljuc)
            najdi(vozlisce.levo, kljuc);
        else if (kljuc > vozlisce.kljuc)
            najdi(vozlisce.desno, kljuc);
        else
            return;
    }

    public void izbrisi(int kljuc) {
        koren = izbrisi(koren, kljuc);
    }

    private Vozlisce izbrisi(Vozlisce vozlisce, int kljuc) {
        if (vozlisce == null) {
            return vozlisce;
        }
        if (kljuc < vozlisce.kljuc) {
            vozlisce.levo = izbrisi(vozlisce.levo, kljuc);
        } else if (kljuc > vozlisce.kljuc) {
            vozlisce.desno = izbrisi(vozlisce.desno, kljuc);
        } else {
            if (vozlisce.stevec > 1) {
                vozlisce.stevec--;
                posodobi(vozlisce);
                return vozlisce;
            }
            if (vozlisce.levo == null || vozlisce.desno == null) {
                return (vozlisce.levo != null) ? vozlisce.levo : vozlisce.desno;
            } else {
                Vozlisce pred = najvecji(vozlisce.levo);
                vozlisce.kljuc = pred.kljuc;
                vozlisce.stevec = pred.stevec;
                pred.stevec = 1;
                vozlisce.levo = izbrisi(vozlisce.levo, pred.kljuc);
            }
        }
        return uravnotezi(vozlisce);
    }

    private Vozlisce najvecji(Vozlisce vozlisce) {
        while (vozlisce.desno != null) {
            vozlisce = vozlisce.desno;
        }
        return vozlisce;
    }

    public void premiPregled() {
        if (koren == null) {
            System.out.println("empty");
        } else {
            StringBuffer sb = new StringBuffer();
            premiPregled(koren, sb);
            System.out.println(sb.toString());
        }
    }

    private void premiPregled(Vozlisce vozlisce, StringBuffer sb) {
        if (vozlisce == null)
            return;
        if (sb.length() > 0) {
            sb.append(",");
        }
        sb.append(vozlisce.kljuc + "/" + vozlisce.stevec);
        premiPregled(vozlisce.levo, sb);
        premiPregled(vozlisce.desno, sb);
    }

    public void najnizjiSkupniPredhodnik(int a, int b) {
        if (!obstaja(koren, a) || !obstaja(koren, b)) {
            System.out.println("x");
            return;
        }

        Vozlisce vozlisce = koren;
        while (vozlisce != null) {
            if (a < vozlisce.kljuc && b < vozlisce.kljuc) {
                vozlisce = vozlisce.levo;
            } else if (a > vozlisce.kljuc && b > vozlisce.kljuc) {
                vozlisce = vozlisce.desno;
            } else {
                System.out.println(vozlisce.kljuc);
                return;
            }
        }
    }

    private boolean obstaja(Vozlisce vozlisce, int kljuc) {
        while (vozlisce != null) {
            if (kljuc < vozlisce.kljuc) {
                vozlisce = vozlisce.levo;
            } else if (kljuc > vozlisce.kljuc) {
                vozlisce = vozlisce.desno;
            } else {
                return true;
            }
        }
        return false;
    }

    public void vsotaVMejah(int spodnjaMeja, int zgornjaMeja) {
        int vsota = vsotaVMejah(koren, spodnjaMeja, zgornjaMeja);
        System.out.println(vsota);
    }

    private int vsotaVMejah(Vozlisce vozlisce, int spodnjaMeja, int zgornjaMeja) {
        if (vozlisce == null)
            return 0;
        int vsota = 0;
        if (vozlisce.kljuc > spodnjaMeja) {
            vsota += vsotaVMejah(vozlisce.levo, spodnjaMeja, zgornjaMeja);
        }
        if (vozlisce.kljuc >= spodnjaMeja && vozlisce.kljuc <= zgornjaMeja) {
            vsota += vozlisce.kljuc * vozlisce.stevec;
        }
        if (vozlisce.kljuc < zgornjaMeja) {
            vsota += vsotaVMejah(vozlisce.desno, spodnjaMeja, zgornjaMeja);
        }
        return vsota;
    }

    public void ktiNajmanjsi(int indeks) {
        if (indeks < 1) {
            indeks = 1;
        }
        Integer rezultat = ktiNajmanjsi(koren, indeks);
        System.out.println(rezultat == null ? "x" : rezultat);
    }

    private Integer ktiNajmanjsi(Vozlisce vozlisce, int k) {
        if (vozlisce == null)
            return null;
        int velikostLevo = velikost(vozlisce.levo);
        if (k <= velikostLevo) {
            return ktiNajmanjsi(vozlisce.levo, k);
        } else if (k <= velikostLevo + vozlisce.stevec) {
            return vozlisce.kljuc;
        } else {
            return ktiNajmanjsi(vozlisce.desno, k - velikostLevo - vozlisce.stevec);
        }
    }
}