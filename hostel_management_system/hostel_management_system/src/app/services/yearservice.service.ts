import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class YearserviceService {

  constructor(private http:HttpClient) { }

  getyears(headers:any)
  {
    return this.http.get('http://localhost:8000/api/getyear',headers);
  }
}
