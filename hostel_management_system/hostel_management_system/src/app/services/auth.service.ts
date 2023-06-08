import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ParamsService } from './params.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  user: any = null;

  constructor(private httppclient: HttpClient,private paramservice:ParamsService) {}

  login(data: any) {
    return this.httppclient.post('http://localhost:8000/api/login', data);
  }
  register(data: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/register',
      data,
      headers
    );
  }
  activateUser(data: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/activateUser',
      data,
      headers
    );
  }
  deactivateUser(data: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/deactivateUser',
      data,
      headers
    );
  }

  getUsers(headers: any) {
    return this.httppclient.get('http://localhost:8000/api/getusers', headers);
  }
  changePassword(data: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/changePassword',
      data,
      headers
    );
  }
  removeRight(data: any, headers: any) {
return    this.httppclient.post(
      'http://localhost:8000/api/removeright',
      data,
      headers
    );
  }
  assignRights(data: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/createrights',
      data,
      headers
    );
  }

  getrights(user: any, headers: any) {
    return this.httppclient.post(
      'http://localhost:8000/api/getrights',
      user,
      headers
    );
  }
  getRole() {
    this.user = this.paramservice.getparam('user');

    return this.user.user_details.role;
  }
  chengePassword() {
    this.user =  this.paramservice.getparam('user');

    return this.user.prompt_change;
  }
  getUserId() {
    this.user = localStorage.getItem('user');

    return JSON.parse(this.user).user_details.user_id;
  }
  getToken() {
    this.user =  this.paramservice.getparam('user');

    return this.user.access_token;
  }
  getHeaders() {
    const headers = new HttpHeaders({
      Accept: 'application/json',
      'Content-Type': 'application/json',
      Authorization: `Bearer ` + this.getToken(),
    });

    return headers;
  }
  authenticated() {
    return localStorage.getItem('user');
  }
  logout() {
    localStorage.clear();
  }
}
