// Source code is decompiled from a .class file using FernFlower decompiler.
import java.util.Random;

public class SingleQueueSimulation {
   public SingleQueueSimulation() {
   }

   public static void main(String[] var0) {
      byte var1 = 10;
      double var2 = 10.0;
      double var4 = 1.5;
      double var6 = 9.5;
      double var8 = 1.0;
      double var10 = 0.0;
      double var12 = 0.0;
      double var14 = 0.0;
      double var16 = 0.0;
      Random var18 = new Random();
      System.out.printf("%-3s%-10s%-10s%-10s%-10s%-10s%-10s%-10s\n", "i", "IAT", "CAT", "SB", "ST", "SE", "WT", "IT");

      for(int var19 = 1; var19 <= var1; ++var19) {
         double var20 = generateNormal(var18, var2, var4);
         var10 += var20;
         double var24 = 0.0;
         double var22;
         double var26;
         if (var10 <= var12) {
            var22 = var12;
            var24 = var12 - var10;
            var14 += var24;
            var26 = 0.0;
         } else {
            var22 = var10;
            var26 = var10 - var12;
            var16 += var26;
         }

         double var28 = generateNormal(var18, var6, var8);
         var12 = var22 + var28;
         System.out.printf("%-3d%-10.3f%-10.3f%-10.3f%-10.3f%-10.3f%-10.3f%-10.3f\n", var19, var20, var10, var22, var28, var12, var24, var26);
      }

      double var30 = var14 / (double)var1;
      double var21 = (var10 - var16) * 100.0 / var10;
      System.out.printf("\n%-30s %.3f\n", "Average Waiting Time:", var30);
      System.out.printf("%-30s %.2f%%\n", "Percentage Capacity Utilization:", var21);
   }

   private static double generateNormal(Random var0, double var1, double var3) {
      double var5 = 0.0;

      for(int var7 = 0; var7 < 12; ++var7) {
         var5 += var0.nextDouble();
      }

      return var1 + var3 * (var5 - 6.0);
   }
}
