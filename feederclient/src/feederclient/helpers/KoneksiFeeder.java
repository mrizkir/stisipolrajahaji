/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package feederclient.helpers;

import javax.
/**
 *
 * @author admin
 */
public class KoneksiFeeder {
    /**
     * host dari feeder
    */
    private String host;
    /**
     * username dari feeder host
    */
    private String username;
    /**
     * password dari feeder password
    */
    private String password;
    /**
     * token feeder dari koneksi
     */
    private String token = null;
    
    /**
     * 
     * @param host
     * @param username
     * @param password 
     */
    public KoneksiFeeder(String host, String username, String password)
    {
        this.host = host;
        this.username = username;
        this.password = password;
    }
    
    /**
     * koneksi
    */
    public void koneksi()
    {
        try
        {
            
            Client client = ClientBuilder.newClient();
            WebTarget target = client.target("http://host:8080/context/rest/method");
            JsonArray response = target.request(MediaType.APPLICATION_JSON).get(JsonArray.class);
        } 
        catch (Exception e) {
            System.out.println("Exception in NetClientGet:- " + e);
        }
    }
    
}
