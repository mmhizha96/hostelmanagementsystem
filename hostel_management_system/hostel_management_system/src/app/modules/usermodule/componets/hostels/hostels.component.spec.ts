import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HostelsComponent } from './hostels.component';

describe('HostelsComponent', () => {
  let component: HostelsComponent;
  let fixture: ComponentFixture<HostelsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HostelsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HostelsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
