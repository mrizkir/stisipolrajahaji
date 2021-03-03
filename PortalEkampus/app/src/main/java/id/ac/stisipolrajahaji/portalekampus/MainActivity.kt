package id.ac.stisipolrajahaji.portalekampus

import android.app.ProgressDialog
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.EditText
import android.widget.Spinner
import com.android.volley.RequestQueue
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import java.lang.Exception

class MainActivity : AppCompatActivity(), View.OnClickListener {

    lateinit var  username:EditText
    lateinit var  userpassword:EditText
    lateinit var btn_login: Button

    var req: RequestQueue?=null
    val host:String="https://api.stisipolrajahaji.ac.id/v3"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)


        val spinner: Spinner = findViewById(R.id.spinnerPage)

        //load daftar halaman atau (role)
        ArrayAdapter.createFromResource(
            this,
            R.array.daftar_page,
            android.R.layout.simple_spinner_item
        ).also { adapter ->
            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
            spinner.adapter=adapter
        }

        username = findViewById(R.id.etUsername)
        userpassword = findViewById(R.id.etUserpassword)

        btn_login = findViewById(R.id.btnLogin)

        btn_login.setOnClickListener(this)
    }

    override fun onClick(v: View?) {
        doLogin()
    }

    fun doLogin()
    {
        var username:String=username.getText().toString()
        var userpassword:String=userpassword.getText().toString()

        req = Volley.newRequestQueue(this)
        val parameters:MutableMap<String,String> = HashMap()
        parameters.put("username",username)
        parameters.put("password",userpassword)


        Log.d("Message",username)
        Log.d("Message",userpassword)
    }

}
