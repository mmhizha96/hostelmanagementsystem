import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class ApplicationService {
  serverIp = 'http://127.0.0.1:8000/api';
  constructor(private httpclient: HttpClient) {}
  applyForRoom(data: any, headers: any) {
    return this.httpclient.post(
      this.serverIp + '/createroomapplication',
      data,
      headers
    );
  }


  getMyallocation(data: any, headers: any) {
    return this.httpclient.post(this.serverIp + '/getstudentallocation', data, headers);
  }

  getroomsToapply(data: any, headers: any) {
    return this.httpclient.post(this.serverIp + '/getmyrooms', data, headers);
  }

  getMybatches(data: any, headers: any) {
    return this.httpclient.post(
      this.serverIp + '/getstudentBatches',
      data,
      headers
    );
  }
}
