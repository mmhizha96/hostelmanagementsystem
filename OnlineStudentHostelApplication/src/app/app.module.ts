
import { DataTablesModule } from 'angular-datatables';

import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/login/login.component';

import { PagenotfoundComponent } from './components/pagenotfound/pagenotfound.component';
import { FormsModule, NgForm } from '@angular/forms';
import { StudentModule } from './modules/student/student.module';
import { HttpClientModule } from '@angular/common/http';
import { AuthService } from './services/auth.service';





@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,


    PagenotfoundComponent,

  ],
  imports: [
    FormsModule,
    BrowserModule,
    AppRoutingModule,
    StudentModule,
    HttpClientModule,
DataTablesModule

  ],
  providers: [AuthService],
  bootstrap: [AppComponent]
})
export class AppModule { }
