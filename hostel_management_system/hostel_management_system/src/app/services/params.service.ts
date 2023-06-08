import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ParamsService {
item:any;
  constructor() { }
  setparam(key:any,value:any){
    localStorage.setItem(key,JSON.stringify(value));
  }
  getparam(key:any){
    this.item=  localStorage.getItem(key);
    return JSON.parse(this.item);
  }
}
