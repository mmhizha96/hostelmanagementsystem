import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { EncriprionserviceService } from './encriprionservice.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  serverip = 'http://127.0.0.1:8000/api';
  token: any;
  token1: any;
  constructor(
    private httpclient: HttpClient,
    private encservice: EncriprionserviceService
  ) {}

  login(data: any) {
    return this.httpclient.post(this.serverip + '/student-login', data);
  }
  getHeaders() {
    this.token = localStorage.getItem('accesstocken');
    var dectoken = this.encservice.decrypt(this.token);
    this.token1 = dectoken;

    const headers = new HttpHeaders({
      Accept: 'pplication/json',
      'Content-Type': 'application/json',
      Authorization: `Bearer ` + this.token1,
    });
    return headers;
  }
}
