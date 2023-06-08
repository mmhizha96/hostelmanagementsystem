import { ComponentFixture, TestBed } from '@angular/core/testing';

import { HostelPreferenceComponent } from './hostel-preference.component';

describe('HostelPreferenceComponent', () => {
  let component: HostelPreferenceComponent;
  let fixture: ComponentFixture<HostelPreferenceComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ HostelPreferenceComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(HostelPreferenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
