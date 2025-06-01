package AVLDrevo;

import java.io.PrintStream;
import java.io.FileOutputStream;
import java.io.IOException;

public class AVLTest {
    public static void main(String[] args) {
        AVLDrevo tree = new AVLDrevo();
        for (int i = 1; i < 2000; i++) {
            for (int j = 0; j < i % 2 + 1; j++) {
                tree.vstavi(i);
            }
        }

        try (PrintStream out = new PrintStream(new FileOutputStream("output.txt"))) {
            System.setOut(out); // Preusmeri System.out v datoteko

            for (int i = 0; i < 1000; i++) {
                tree.ktiNajmanjsi(i);
            }

            System.setOut(System.out); // Vrni System.out na prvotno stanje (pomembno!)

        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}