/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package components;

import java.awt.Color;
import java.awt.Dimension;
import javax.swing.JScrollBar;

/**
 *
 * @author admin
 */
public class ScrollbarCustom extends JScrollBar {
    public ScrollbarCustom() {
        setUI(new ModernScrollbarUI());
        setPreferredSize(new  Dimension(8, 8));
        setForeground(new Color(48, 144, 216));
        setBackground(Color.WHITE);        
        
    }
}
