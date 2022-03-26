/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package id.ac.stisipolrajahaji.feederclient.configuration;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

/**
 *
 * @author admin
 */
public final class Configuration {
    private Connection conn = null;
    private PreparedStatement pstmt;
    private ResultSet rs;
    
    public Configuration() {
        connect();
        createNewTable();
    }
    /**
     * Connect to a sample database
     */
    private void connect() {        
        try
        {
            // db parameters
            String url = "jdbc:sqlite:feederclient.db";
            // create a connection to the database
            conn = DriverManager.getConnection(url);            
            
        } 
        catch (SQLException e) 
        {
            System.out.println(e.getMessage());
        } 
        finally {
            try 
            {
                if (conn != null) 
                {
                    conn.close();
                }
            } 
            catch (SQLException ex) 
            {
                System.out.println(ex.getMessage());
            }
        }
    }
    
    /**
     * Create a new table in the test database     
     */
    private void createNewTable(){
        
        // SQL statement for creating a new table
        String sql = """
                     CREATE TABLE IF NOT EXISTS configuration (
                     config_id integer PRIMARY KEY,
                     config_name text NOT NULL,
                     config_value text NOT NULL
                     );
                     """;
        
        try {
            Statement stmt;
            stmt = conn.createStatement();        
            // create a new table
            stmt.execute(sql);
            
            boolean recordAdded;
            
            for (int i = 1; i < 3; i++) 
            {
                sql = "SELECT config_id FROM configuration WHERE config_id = ?";
                pstmt  = conn.prepareStatement(sql);
                pstmt.setInt(1, i);
                
                rs  = pstmt.executeQuery();
                recordAdded = false;
                while(!rs.next())
                {
                    recordAdded = true;
                }
                
                sql = "INSERT INTO configuration(config_id,config_name,config_value) VALUES (?, ?, ?)";
            
                if(!recordAdded) 
                {
                    pstmt = conn.prepareStatement(sql);
                    pstmt.setInt(1, 1);
                    switch(i)
                    {
                        case 1 -> pstmt.setString(2, "feeder_host");
                        case 2 -> pstmt.setString(2, "feeder_username");
                        case 3 -> pstmt.setString(2, "feeder_password");
                    }
                    
                    pstmt.setString(3, "");
                    pstmt.executeUpdate();
                }
            }
        } 
        catch (SQLException e) 
        {
            System.out.println(e.getMessage());
        }
    }
    
    public String getConfigValue(int config_id) 
    {
        String config_name = "";
        try 
        {
            String sql = "SELECT config_value FROM configuration WHERE config_id = ?";
            pstmt  = conn.prepareStatement(sql);
            pstmt.setInt(1, config_id);

            rs  = pstmt.executeQuery();
            
            while(!rs.next())
            {
                config_name = rs.getString("config_value");
            }
            
        }
        catch (SQLException e) 
        {
            System.out.println(e.getMessage());
        }
        
        return config_name;
    }
    
    public void updateConfigValue(int config_id, String config_value)
    {
        try 
        {
            String sql = "UPDATE configuration SET config_value = ? WHERE config_id = ?";
            pstmt  = conn.prepareStatement(sql);
            pstmt.setInt(1, config_id);
            pstmt.setString(2, config_value);

            pstmt.executeUpdate();
        }
        catch (SQLException e) 
        {
            System.out.println(e.getMessage());
        }
    
    }
}
